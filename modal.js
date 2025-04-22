// modal.js
function funcModal() {
    document.getElementById('pendingModal').style.display = 'block';

}
function lowStockModal() {
    document.getElementById('lowStockModal').style.display = 'block';

}
function deModal() {
    document.getElementById('deliveredModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('pendingModal').style.display = 'none';
    document.getElementById('deliveredModal').style.display = 'none';
    document.getElementById('lowStockModal').style.display = 'none';


}

window.onclick = function(event) {
    var modal = document.getElementById('myModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
window.onclick = function(event) {
    var modal = document.getElementById('myMode');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
window.onclick = function(event) {
    var modal = document.getElementById('lowStock');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}