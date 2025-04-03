// Ngăn sự kiện zoom bằng phím Ctrl + cuộn chuột
document.addEventListener('wheel', function(event) {
    if (event.ctrlKey) {
        event.preventDefault();
    }
}, { passive: false });

// Ngăn sự kiện zoom bằng phím Ctrl + +/- hoặc Ctrl + 0
document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && (event.key === '+' || event.key === '-' || event.key === '0')) {
        event.preventDefault();
    }
});



// Hàm chuyển hướng đến route
function navigateTo(route) {
    window.location.href = `/SUBMIT/public/${route}`;
}