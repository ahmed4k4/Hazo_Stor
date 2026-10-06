document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach(button => {
        button.addEventListener("click", e => {
            if (!confirm("هل أنت متأكد من الحذف؟")) {
                e.preventDefault();
            }
        });
    });
});
