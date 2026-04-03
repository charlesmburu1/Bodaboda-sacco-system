<?php
$conn = new mysqli("localhost", "root", "", "your_database");

$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<h2>Contact Messages</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
    <th>Date</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['message'] ?></td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>
</table>