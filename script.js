document.querySelectorAll('.scroll-container').forEach(container => {
  const scrollContainer = container.querySelector('.horizontal-scroll');
const scrollLeftBtn = container.querySelector('.scroll-left');
const scrollRightBtn = container.querySelector('.scroll-right');

scrollLeftBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
        left: -200, 
        behavior: 'smooth'
    });
});

scrollRightBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
        left: 200, 
        behavior: 'smooth'
    });
});

function scrollHorizontally(distance) {
    const container = document.querySelector('.horizontal-scroll');
    container.style.transform = `translateX(${distance}px)`;
}

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
      alert('Product added to cart!');
    });
  });
  
  document.querySelectorAll('.favorite').forEach(button => {
    button.addEventListener('click', () => {
      button.classList.toggle('favorited');
    });
  });
});




  const carousel = document.querySelector('.carousel');
const slides = document.querySelectorAll('.slide');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

let currentIndex = 0;

function updateCarousel() {
  const slideWidth = slides[0].clientWidth; 
  const offset = -currentIndex * slideWidth; 
  carousel.style.transform = `translateX(${offset}px)`; 
}

nextButton.addEventListener('click', () => {
  currentIndex = (currentIndex + 1) % slides.length; 
  updateCarousel();
});

prevButton.addEventListener('click', () => {
  currentIndex = (currentIndex - 1 + slides.length) % slides.length; 
  updateCarousel();
});

function validateForm() {
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const subject = document.getElementById("subject").value.trim();
  const message = document.getElementById("message").value.trim();
  if (!name) {
      alert("Please enter your full name.");
      return false;
  }
  if (!email) {
      alert("Please enter your email address.");
      return false;
  }
  if (!validateEmail(email)) {
      alert("Please enter a valid email address.");
      return false;
  }
  if (!subject) {
      alert("Please enter the subject.");
      return false;
  }
  if (!message) {
      alert("Please enter your message.");
      return false;
  }
  alert("Your message has been sent successfully!");
      document.location="./home.html";
      return false;
}
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}



function validateLogin() {
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  if (!email) {
      alert("Please enter your email address.");
      return false;
  }
  if (!password) {
      alert("Please enter your password.");
      return false;
  }
  if (!validateEmail(email)) {
      alert("Please enter a valid email address.");
      return false;
  }
  alert("Login successful!");

    document.location="./home.html";
    return false;

}
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}


   


  