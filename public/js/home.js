document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".toggle-body").forEach(function(toggleBtn) {
        toggleBtn.addEventListener("click", function() {
            let cardBody = this.closest(".layanan-card").querySelector(".card-body");
            let icon = this.querySelector("i");
            
            if (cardBody.style.display === "none") {
                cardBody.style.display = "block";
                this.classList.replace("fa-plus", "fa-minus");
            } else {
                cardBody.style.display = "none";
                this.classList.replace("fa-minus", "fa-plus");
            }
        });
    });

    // Sembunyikan semua card-body saat halaman pertama kali dimuat
    document.querySelectorAll(".card-body").forEach(function(cardBody) {
        cardBody.style.display = "none";
    });

    // Ubah semua ikon menjadi fa-plus saat pertama kali dimuat
    document.querySelectorAll(".toggle-body i").forEach(function(icon) {
        icon.classList.replace("fa-minus", "fa-plus");
    });
});