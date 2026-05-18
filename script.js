document.addEventListener("DOMContentLoaded", function () {
    // 1. Dark/Light Mode Theme Toggle Functionality
    const themeToggleBtn = document.getElementById("themeToggle");
    const currentTheme = localStorage.getItem("theme") ? localStorage.getItem("theme") : "light";

    // Set initial theme based on saved preferences
    if (currentTheme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
        if(themeToggleBtn) themeToggleBtn.innerHTML = "☀️ Light Mode";
    } else {
        document.documentElement.setAttribute("data-theme", "light");
        if(themeToggleBtn) themeToggleBtn.innerHTML = "🌙 Dark Mode";
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", function () {
            let theme = document.documentElement.getAttribute("data-theme");
            if (theme === "light") {
                document.documentElement.setAttribute("data-theme", "dark");
                localStorage.setItem("theme", "dark");
                themeToggleBtn.innerHTML = "☀️ Light Mode";
            } else {
                document.documentElement.setAttribute("data-theme", "light");
                localStorage.setItem("theme", "light");
                themeToggleBtn.innerHTML = "🌙 Dark Mode";
            }
        });
    }

    // 2. Generic Frontend Validation Functions
    const forms = document.querySelectorAll(".needs-validation");
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        }, false);
    });
});

// Custom BMI Client Side calculation
function calculateBMIFrontend() {
    const weight = parseFloat(document.getElementById("weight").value);
    const height = parseFloat(document.getElementById("height").value) / 100; // convert cm to meters

    if (weight > 0 && height > 0) {
        const bmi = (weight / (height * height)).toFixed(2);
        let category = "";

        if (bmi < 18.5) category = "Underweight";
        else if (bmi < 24.9) category = "Normal Weight";
        else if (bmi < 29.9) category = "Overweight";
        else category = "Obese";

        document.getElementById("bmiResultText").innerHTML = `<strong>Your BMI:</strong> ${bmi} (${category})`;
        document.getElementById("resultAlert").classList.remove("d-none");
    }
}