// Function untuk menampilkan atau menyembunyikan sidebar
function toggleMenu() {
    document.getElementById("nav-list").classList.toggle("active");
}

// document.addEventListener("DOMContentLoaded", () => {
//     const sidebar = document.getElementById("sidebar");
//     const sidebarToggler = document.querySelector(".sidebar-toggler");
//     const menuToggler = document.querySelector(".menu-toggler");

//     if (sidebar && sidebarToggler && menuToggler) {
//         // Toggle sidebar collapse
//         sidebarToggler.addEventListener("click", () => {
//             sidebar.classList.toggle("collapsed");
//         });

//         // Toggle menu in mobile view
//         menuToggler.addEventListener("click", () => {
//             sidebar.classList.toggle("menu-active");
//         });
//     }
// });

/* Dummy Login */
function validateLogin() {
    // Data Dummy
    validEmail = "user@gmail.com";
    validPassword = "user12345";
    var error = document.getElementById("error")

    // Ambil nilai dari input
    emailInput = document.getElementById("email").value;
    passwordInput = document.getElementById("password").value;

    

    // Validasi
    if (emailInput === "" || passwordInput === "") {
        error.innerHTML = "<span>email atau password tidak boleh kosong.</span>";
        error.classList.remove("hidden");
        error.classList.add("visible");
        console.log("Email atau Password kosong");
        return;
    }

    // Validasi jika email dan password benar
    if (emailInput === validEmail && passwordInput === validPassword) {

        window.location.href = "../../main/home.html";
        console.log("Berhasil Login");
        error.innerHTML = "";
        error.classList.add("hidden");
    } else {
        // Jika email atau password salah
        error.innerHTML = "<span>email atau password salah</span>";
        error.classList.remove("hidden");
        error.classList.add("visible");
        console.log("Email atau Password salah");
    }
}


function validateRegister() {

    var error = document.getElementById("error")

    nameInput = document.getElementById("name").value;
    emailInput = document.getElementById("email").value;
    passwordInput = document.getElementById("password").value;

    error.innerHTML = "";
    error.classList.add("hidden");

    if (nameInput === "" || emailInput === "" || passwordInput === "") {
        error.innerHTML = "<span>form tidak boleh kosong.</span>";
        error.classList.remove("hidden");
        error.classList.add("visible");
        console.log("Form tidak boleh kosong");
        return;
    } 
    window.location.href = "../../main/home.html";
    console.log("Register Berhasil");
}

function showCorrect(button, isCorrect) {
    if (isCorrect) {
        button.classList.add("correct");
        alert("Jawaban benar!");
    } else {
        alert("Jawaban salah, coba lagi!");
    }
}


let correctOrder = ['red', 'blue', 'yellow', 'green'];  // Urutan warna yang benar
let userOrder = [];

// Fungsi untuk memungkinkan drop
function allowDrop(ev) {
    ev.preventDefault();
}

// Fungsi untuk menangani drag event
function drag(ev) {
    ev.dataTransfer.setData("color", ev.target.id);
}

// Fungsi untuk menangani drop event
function drop(ev) {
    ev.preventDefault();
    let data = ev.dataTransfer.getData("color");
    let colorBox = document.getElementById(data);
    let target = ev.target;

    // Pindahkan warna ke tempat yang benar
    target.style.backgroundColor = colorBox.style.backgroundColor;
    target.appendChild(colorBox);
    userOrder.push(colorBox.style.backgroundColor);

    // Periksa apakah urutan benar
    if (userOrder.length === correctOrder.length) {
        checkWin();
    }
}

// Fungsi untuk memeriksa apakah urutan yang dipilih benar
function checkWin() {
    let message = document.getElementById('message');
    if (JSON.stringify(userOrder) === JSON.stringify(correctOrder)) {
        message.textContent = "Selamat! Kamu berhasil!";
        message.style.color = '#32cd32';  // Hijau
    } else {
        message.textContent = "Coba lagi! Urutannya belum benar.";
        message.style.color = '#ff6347';  // Merah
    }
}

// Fungsi untuk memulai ulang permainan
function startGame() {
    userOrder = [];
    document.querySelectorAll('.drag-target').forEach(target => {
        target.style.backgroundColor = '';
        target.innerHTML = '';
    });

    document.getElementById('message').textContent = '';
}

startGame();  // Memulai permainan saat pertama kali dimuat

let motions = [
    "Tepuk tangan 3 kali!", 
    "Lompat 5 kali!", 
    "Putar badan 360 derajat!", 
    "Sentuh jari kaki kiri!", 
    "Angkat tangan kanan ke atas!"
];
let currentMotionIndex = 0;

function startKinestheticGame() {
    let message = document.getElementById('message');
    let instruction = document.getElementById('instruction');
    let motionText = document.getElementById('motion-text');

    // Reset message and instructions
    message.textContent = '';
    instruction.textContent = 'Tunggu sebentar... akan ada perintah gerakan yang harus kamu ikuti.';

    // Hide start button and show reset button
    document.getElementById('start-button').style.display = 'none';
    document.getElementById('reset-button').style.display = 'block';

    // Start motion
    currentMotionIndex = 0;
    showNextMotion();
}

function showNextMotion() {
    if (currentMotionIndex < motions.length) {
        let motion = motions[currentMotionIndex];
        let motionText = document.getElementById('motion-text');
        motionText.textContent = motion;
        
        setTimeout(() => {
            currentMotionIndex++;
            showNextMotion();
        }, 5000);  // Wait for 5 seconds before showing next motion
    } else {
        // End of game
        let message = document.getElementById('message');
        message.textContent = "Permainan selesai! Hebat sekali, kamu sudah menyelesaikan semua gerakan!";
        message.style.color = "#32cd32";  // Green color
        document.getElementById('instruction').textContent = "Kamu sangat hebat! Yuk, coba lagi!";
    }
}