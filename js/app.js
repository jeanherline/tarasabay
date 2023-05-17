const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");

inputs.forEach((inp) => {
  inp.addEventListener("focus", () => {
    inp.classList.add("active");
  });
  inp.addEventListener("blur", () => {
    if (inp.value != "") return;
    inp.classList.remove("active");
  });
});

toggle_btn.forEach((btn) => {
  btn.addEventListener("click", () => {
    main.classList.toggle("sign-up-mode");
  });
});

function moveSlider() {
  let index = this.dataset.value;

  let currentImage = document.querySelector(`.img-${index}`);
  images.forEach((img) => img.classList.remove("show"));
  currentImage.classList.add("show");

  const textSlider = document.querySelector(".text-group");
  textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

  bullets.forEach((bull) => bull.classList.remove("active"));
  this.classList.add("active");
}

bullets.forEach((bullet) => {
  bullet.addEventListener("click", moveSlider);
});

// modal
/*=============== SHOW MODAL ===============*/
const showModal = (openButton, modalContent) => {
  const openBtn = document.getElementById(openButton),
    modalContainer = document.getElementById(modalContent)

  if (openBtn && modalContainer) {
    openBtn.addEventListener('click', () => {
      modalContainer.classList.add('show-modal')
    })
  }
}
showModal('open-modal', 'modal-container')

/*=============== CLOSE MODAL ===============*/
const closeBtn = document.querySelectorAll('.close-modal')

function closeModal() {
  const modalContainer = document.getElementById('modal-container')
  modalContainer.classList.remove('show-modal')
}
closeBtn.forEach(c => c.addEventListener('click', closeModal))

// sign up
const steps = document.querySelectorAll('.step');
const nextBtns = document.querySelectorAll('.next-btn');
const prevBtns = document.querySelectorAll('.prev-btn');

nextBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const currentStep = btn.closest('.step');
    const nextStep = steps[parseInt(currentStep.getAttribute('data-step'))];

    currentStep.style.display = 'none';
    nextStep.style.display = 'block';
  });
});

prevBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const currentStep = btn.closest('.step');
    const prevStep = steps[parseInt(currentStep.getAttribute('data-step')) - 2];

    currentStep.style.display = 'none';
    prevStep.style.display = 'block';
  });
});
