document.addEventListener("DOMContentLoaded", function () {
  const appointment = document.querySelector(".book-your-appointment");
  const cancel = document.querySelector(".cancel-cross");
  const chatWidget = document.querySelector(".chat-widget");

  // Toggle appointment and chat
  window.hide = function () {
    if (appointment.style.display === "block") {
      appointment.style.display = "none";
      cancel.style.display = "none";
    } else {
      chatWidget.style.display = "none";
      appointment.style.display = "block";
    }
  };

  window.show = function () {
    appointment.style.display = "none";
    chatWidget.style.display = "block";
  };

  // Scroll animations
  const elementsToAnimate = [
    {
      left: document.querySelector('.why-choose-us-container-left'),
      right: document.querySelector('.why-choose-us-container-right')
    },
    {
      left: document.querySelector('.our-services-container-left'),
      right: document.querySelector('.our-services-container-right')
    },
    {
      left: document.querySelector('.exclusive-bridal-container-left'),
      right: document.querySelector('.exclusive-bridal-container-right')
    }
  ];
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add(
          entry.target.classList.contains('exclusive-bridal-container-left') ||
          entry.target.classList.contains('our-services-container-left') ||
          entry.target.classList.contains('why-choose-us-container-left')
            ? 'animate-left'
            : 'animate-right'
        );
      }
    });
  }, { threshold: 0.3 });
  
  elementsToAnimate.forEach(pair => {
    if (pair.left) observer.observe(pair.left);
    if (pair.right) observer.observe(pair.right);
  });

  // Service content
  const serviceContent = {
    Men: {
      image: "../images/services_men.png",
      list: ["Pre-Wedding & Bridal", "Body", "Skin", "Hair"],
      descriptions: {
        "Pre-Wedding & Bridal": "Naturals offers exclusive wedding grooming services for grooms, including tailored haircuts, skin treatments, facials, body polishing, and relaxing massages.",
        Body: "Naturals offers a range of men's body care services, including relaxing massages, body scrubs, exfoliation, detox treatments, body polishing, and full-body pampering, leaving you refreshed and revitalized.",
        Skin: "At Naturals, men's skincare is elevated with advanced treatments like deep cleansing, hydrating facials, exfoliation, brightening, and anti-aging solutions, ensuring total rejuvenation and care.",
        Hair: "Naturals offers premium men's grooming with expert barbering, precise styling, fades, trims, relaxing treatments, hair coloring, and luxurious pampering."
      }
    },
    Women: {
      image: "../images/services_women_v2.png",
      list: ["Pre-Wedding & Bridal", "Body", "Skin", "Hair"],
      descriptions: {
        "Pre-Wedding & Bridal": "Naturals specializes in bridal beauty with customized hair styling, flawless makeup, skin treatments, and pampering services to ensure you look and feel stunning on your special day.",
        Body: "Naturals provides women's body services including relaxing massages, exfoliating scrubs, detoxifying treatments, body wraps, and full-body pampering, ensuring a revitalized and refreshed experience.",
        Skin: "Naturals offers comprehensive women's skin services, featuring rejuvenating facials, deep cleansing, hydration treatments, exfoliation, and anti-aging solutions to achieve radiant, flawless skin.",
        Hair: "At Naturals, women's hair services include expert cuts, vibrant coloring, luxurious conditioning, precise styling, and smoothing treatments, all tailored to enhance your unique beauty."
      }
    }
  };
  
  const tabs = document.querySelectorAll(".our-services-container-left ul:first-of-type li");
  const serviceList = document.querySelector(".our-services-container-left ul:nth-of-type(2)");
  const serviceImage = document.getElementById("service-image");
  const serviceDescription = document.querySelector(".our-services-container-left p");
  
  let currentCategory = "Men";
  
  serviceImage.classList.add("fade-image");
  serviceDescription.classList.add("fade-text");
  
  function updateSubItems() {
    const { list, descriptions } = serviceContent[currentCategory];
    serviceList.innerHTML = "";
  
    list.forEach(item => {
      const li = document.createElement("li");
      li.textContent = item;
      li.classList.add("sub-tab");
  
      li.addEventListener("click", () => {
        serviceList.querySelectorAll("li").forEach(li => li.classList.remove("active"));
        li.classList.add("active");
  
        serviceDescription.classList.add("fade-out");
        setTimeout(() => {
          serviceDescription.textContent = descriptions[item];
          serviceDescription.classList.remove("fade-out");
        }, 300);
      });
  
      serviceList.appendChild(li);
    });
  
    // Trigger first item click to load description
    const firstItem = serviceList.querySelector("li");
    if (firstItem) firstItem.click();
  }
  
  // Gender tabs
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
  
      currentCategory = tab.textContent.trim();
  
      serviceImage.classList.add("fade-out");
      serviceDescription.classList.add("fade-out");
  
      setTimeout(() => {
        serviceImage.src = serviceContent[currentCategory].image;
        serviceImage.classList.remove("fade-out");
        updateSubItems();
      }, 300);
    });
  });
  
  // Activate default tab
  tabs[0].classList.add("active");
  updateSubItems();
});

// Pricing

document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".select-btn");
  const selectedPlanDiv = document.getElementById("selected-plan");

  buttons.forEach(button => {
    button.addEventListener("click", () => {
      const card = button.closest(".pricing-card");
      const planName = card.getAttribute("data-plan");
      selectedPlanDiv.textContent = `You have selected the "${planName}" plan.`;
    });
  });
});
