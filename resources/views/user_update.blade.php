<html>
<head>
<title>Student Management | Edit</title>
</head>
<body>
<form action = "/edit/<?php echo $users->doctor_id; ?>" method = "post">
<input type = "hidden" name = "_token" value = "<?php echo csrf_token(); ?>">
<table>
<tr>
<td>First Name</td>

<td>
<input type="text" name="name" value="{{ isset($users) ? $users->name : old('name') }}"> </td>
</tr>
<tr>
<td>Last Name</td>
<td>
<input type="text" name="lastname" value="{{ isset($users) ? $users->lastname : old('lastname') }}"> </td>
</tr>
<tr>
<td>Password</td>
<td>
<input type="text" name="password" value="{{ isset($users) ? $users->password : old('password') }}"> </td>
</tr>
<tr>
</tr>
<tr>
<td colspan = '2'>
<input type = 'submit' value = "แก้ไขข้อมูล" />
</td>
</tr>
</table>
</form>
</body>
</html>