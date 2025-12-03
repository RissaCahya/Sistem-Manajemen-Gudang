const cards = document.querySelectorAll(".card");
let current = 0;

function updateCarousel() {
  cards.forEach((card, i) => {
    card.classList.remove("left", "active", "right");
    if(i === current) card.classList.add("active");
    else if(i === (current - 1 + cards.length) % cards.length) card.classList.add("left");
    else if(i === (current + 1) % cards.length) card.classList.add("right");
  });
}

// NEXT / PREV BUTTON
document.getElementById("nextBtn").addEventListener("click", () => {
  current = (current + 1) % cards.length;
  updateCarousel();
});

document.getElementById("prevBtn").addEventListener("click", () => {
  current = (current - 1 + cards.length) % cards.length;
  updateCarousel();
});

// AUTO SLIDE
setInterval(() => {
  current = (current + 1) % cards.length;
  updateCarousel();
}, 3000);

// KLIK CARD MASUK PROFIL
cards.forEach(card => {
  card.addEventListener("click", () => {
    const link = card.getAttribute("data-link");
    if(link) window.location.href = link;
  });
});

updateCarousel();
