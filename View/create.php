<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form id="create-race-form">
    <input type="text" id="race-name" placeholder="Race Name" required>
    <button type="button" onclick="createRace()">Create Race</button>
</form>

<p id="race-number"></p> 
</body>
</html>

<script>
function createRace() {
    const name = document.getElementById('race-name').value;

    fetch('create_back.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.replace("index.php?num="+data.race_number);
        document.getElementById('race-number').innerText = 'Race Number: ' + data.race_number;
    });
}
</script>