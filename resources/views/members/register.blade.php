<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Registration</title>
</head>
<body>
  <h2>Become a Member</h2>

  <form method="POST" action="{{ route('member.register') }}">
    @csrf
    <label>Company Name:</label><br>
    <input type="text" name="company_name" required><br><br>

    <label>Contact Person:</label><br>
    <input type="text" name="contact_person"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone"><br><br>

    <label>Address:</label><br>
    <textarea name="address"></textarea><br><br>

    <label>Document:</label><br>
    <input type="file" name="document"><br><br>

    <button type="submit">Register</button>
  </form>
</body>
</html>
