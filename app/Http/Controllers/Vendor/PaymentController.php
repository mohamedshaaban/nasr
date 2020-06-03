<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Banks;
use App\Models\VendorCommissions;
use App\Models\Vendors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Validator;
use App\Models\VendorBankInformation;
use App\Models\VendorBankInfoChangeRequest;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Finance;
use Carbon\Carbon;
use App\Models\VendorChangeRequestLogs;
use App\Models\VendorOrdersDetails;
use App\Models\FinanceTransferredPdf;
// use PDF;
use Auth;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $permission = Auth::guard('vendor')->user()->permission;
            if( $permission == Vendors::VENDOR_FULL_ACCESS || $permission == Vendors::VENDOR_SALES_ACCESS)
            {

                return $next($request);

            }
            return redirect('/vendor/404');
        });

    }
    public function index()
    {
        $auth = \Auth::guard('vendor')->user();
        $banks = Banks::where('status' , 1 )->get();

        $bankInfo = VendorBankInformation::where('vendor_id', $auth->id)->first();
        if(!$bankInfo)
        {
            $record = new VendorBankInformation();
            $bankInfo = (object)array_combine( $record->getFillable(), array_fill( 0, count( $record->getFillable() ), '' ) );

        }

        $transferredPayments = '';
        $pendingPayments = '';
        return view('vendors.payment.index')->with([
            'bankInfo' => $bankInfo,
            'transferredPayments' => $transferredPayments,
            'pendingPayments' => $pendingPayments,
            'banks'=>$banks
        ]);
    }

    public function changeBankInfo(Request $request)
    {
        $auth = \Auth::guard('vendor')->user();

        // validations
        $this->changeBankInfoValidation($request->all())->validate();

        $bankData = VendorBankInformation::create([
            'vendor_id' => $auth->id,
            'account_name' => $request->account_name,
            'address' => $request->address,
            'bank_id' => $request->bank_id,
            // 'account_number' => '',
            'iban' => $request->iban,
        ]);



        Session::flash('success', 'Bank Information request has been sended successfully');
        return redirect()->back();
    }

    public function downloadTransferredPaymnet(Request $request, $file_name)
    {
        $auth = \Auth::guard('vendor')->user();
        $path = storage_path('/vendors/finance/' . $auth->id . '/' . $file_name);

        return response()->download($path);
    }

    protected function changeBankInfoValidation($data)
    {
        return Validator::make($data, [
            'account_name' => 'required',
            'address' => 'required',
            'bank_id' => 'required',
            // 'account_number' => 'required',
            'iban' => 'required'
        ]);
    }

    protected function getTransferredPayment($auth)
    {
        $financeTransferredPdf = FinanceTransferredPdf::where('vendor_id', $auth->id)
            ->get();

        return $financeTransferredPdf;
    }

    protected function getPendingPayment($auth)
    {
        $vendorOrderDetails = VendorOrdersDetails::with(['order' , 'order.User'])
            ->where('vendor_id', $auth->id)
            ->where('transferred', 0)
            ->where('paid_amount', '>', 0)
            // ->whereRaw('paid_amount + delivery_charge_value_kd  < order_total')
            ->where('order_type', Order::NEW_ORDER)
            ->get();

        return $vendorOrderDetails;
    }
public function commission(Request $request)
{
    $auth = \Auth::guard('vendor')->user();
    $vendor_id = $auth->id ;

    if($auth->parent_id != 0)
    {
        $vendor_id = $auth->parent_id;
    }
    
    $commission = VendorCommissions::where('vendor_id', $vendor_id)->first();
    return view('vendors.payment.commission')->with(['commission'=>$commission]);

}
protected function storeCommission(Request $request)
{

     $this->validation($request->all());
    $auth = \Auth::guard('vendor')->user();
    $vendor_id = $auth->id ;

    if($auth->parent_id != 0)
    {
        $vendor_id = $auth->parent_id;
    }
    $commission = VendorCommissions::updateOrCreate(['id'=>$request->commissionId],[
        'vendor_id'=>$vendor_id,
        'fixed'=>$request->fixed,
        'precentage'=>$request->precentage,
    ]);
    return redirect()->back();
}

    protected function validation( $data ) {


        return Validator::make( $data, [
            'fixed'      => 'required|numeric',


            'precentage'  => 'required|numeric'
        ] );
    }

}
