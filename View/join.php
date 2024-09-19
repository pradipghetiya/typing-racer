<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form id="join-race-form">
    <input type="text" id="username" placeholder="Username" required>
    <input type="number" id="race-number-input" placeholder="Race Number" required>
    <button type="button" onclick="joinRace()">Join Race</button>
</form>
</body>
</html>

<script>
function joinRace() {
    const username = document.getElementById('username').value;
    const raceNumber = document.getElementById('race-number-input').value;

    fetch('join_back.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ username, race_number: raceNumber })
    })
    .then(response => response.json())
    .then(data => window.location.replace("joinindex.php?paraId="+data.paraId+"&num="+raceNumber));

    
}
</script>