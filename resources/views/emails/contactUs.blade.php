<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contact Us</title>
</head>

<body>
<table width="100%" style="min-width:1000px;" border="0" cellspacing="0" cellpadding="20">
    <tr>
        <td height="130" align="center" valign="top" bgcolor="#cfd6de">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" height="68" style="padding:5px;" bgcolor="#FFFFFF"><img
                            src="http://bastaat.com/images/logo.png" width="160" height="99"/></td>
                    <td width="50%" align="right" valign="middle" bgcolor="#FFFFFF"
                        style="font-family:Verdana, Geneva, sans-serif; font-size:16px; line-height:normal; padding:5px">

                </tr>
                <tr>
                    <td><label>Name : </label></td>
                    <td> {{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td><label>Email : </label></td>
                    <td> {{ $data['email'] }}</td>
                </tr>
                <tr>
                    <td><label>Mobile : </label></td>
                    <td> {{ $data['mobile'] }}</td>
                </tr>
                <tr>
                    <td><label>Message : </label></td>
                    <td> {{ $data['message'] }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
