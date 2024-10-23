function toggleMenu() {
    const navUl = document.querySelector('nav ul');
    navUl.classList.toggle('show');
}

function submitVote() {
    const checkedBoxes = document.querySelectorAll('input[name="vote"]:checked');
    if (checkedBoxes.length === 1) {
        const selectedCandidate = checkedBoxes[0].value;
        alert("You have voted for: " + selectedCandidate.replace('-', ' '));
        // Submit vote to the backend logic here (e.g., AJAX call)
    } else if (checkedBoxes.length > 1) {
        alert("Please vote for only one candidate!");
    } else {
        alert("Please select a candidate to vote for.");
    }
}

function showAlert() {
    alert("This field cannot be edited.");
}