/* ========== JAM REALTIME ========== */
function updateClock() {
  const clockEl = document.getElementById("clock");
  if (!clockEl) return;
  const now = new Date();
  const h = String(now.getHours()).padStart(2, "0");
  const m = String(now.getMinutes()).padStart(2, "0");
  const s = String(now.getSeconds()).padStart(2, "0");
  clockEl.textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();

/* ========== HERO SLIDER ========== */
let currentSlide = 0;
const slides = document.querySelectorAll(".hero-slider .slide");
const dots = document.querySelectorAll(".slider-dots .dot");

function showSlide(n) {
  if (slides.length === 0) return;
  slides.forEach((s) => s.classList.remove("active"));
  dots.forEach((d) => d.classList.remove("active"));
  currentSlide = (n + slides.length) % slides.length;
  slides[currentSlide].classList.add("active");
  dots[currentSlide].classList.add("active");
}
function moveSlide(dir) {
  showSlide(currentSlide + dir);
}
function goSlide(n) {
  showSlide(n);
}

if (slides.length > 0) {
  setInterval(() => moveSlide(1), 5000);
}

/* ========== TOGGLE MENU MOBILE ========== */
function toggleMenu() {
  document.querySelector(".nav-menu").classList.toggle("open");
}

/* ========== SEARCH ANGGOTA ========== */
const searchInput = document.getElementById("searchAnggota");
if (searchInput) {
  searchInput.addEventListener("input", function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll(".anggota-card").forEach((card) => {
      const text = card.dataset.search || "";
      card.style.display = text.includes(q) ? "" : "none";
    });
  });
}

/* ========== MODAL FOTO ========== */
function bukaFoto(src, caption) {
  const modal = document.getElementById("modalFoto");
  document.getElementById("modalImg").src = src;
  document.getElementById("modalCaption").textContent = caption;
  modal.style.display = "flex";
}
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    const modal = document.getElementById("modalFoto");
    if (modal) modal.style.display = "none";
  }
});

/* ========== SMOOTH SCROLL ========== */
document.querySelectorAll('a[href^="#"]').forEach((link) => {
  link.addEventListener("click", function (e) {
    const href = this.getAttribute("href");
    if (href === "#") return;
    const target = document.querySelector(href);
    if (target) {
      e.preventDefault();
      const top = target.getBoundingClientRect().top + window.pageYOffset - 90;
      window.scrollTo({ top, behavior: "smooth" });
      document.querySelector(".nav-menu").classList.remove("open");
    }
  });
});

console.log("%cKelas A Loaded", "color: #00bcd4; font-weight: bold;");
