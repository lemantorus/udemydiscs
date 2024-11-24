let offset = 0;
let canLoad = true;
const amountEl = document.querySelector(".results-amount p");
function getRequestBody() {
  return JSON.stringify({
    query: document.querySelector("#searchQuery").value,
    category: document.querySelector("#categories").value,
    languages: Array.from(
      document.querySelector(".langs-wrapper").querySelectorAll("input")
    )
      .filter((input) => input.checked)
      .map((item) => item.value),
    rating: {
      from: +document.querySelector(".rating-f").value,
      to: +document.querySelector(".rating-s").value,
    },
    students: "", // Updated based on actual input if needed
  });
}

function setupForm(languages, categories) {
  function genOptionsHtml(list) {
    return list
      .map((item) => `<option value="${item}">${item}</option>`)
      .join("");
  }

  function generateCheckboxesHtml(list) {
    return list
      .map(
        (item) =>
          `<label><input type="checkbox" name="${item}" value="${item}">${item}</label>`
      )
      .join("");
  }

  const catEl = document.querySelector("#categories");
  const langEl = document.querySelector(".langs-wrapper");

  catEl.innerHTML = genOptionsHtml(categories);
  langEl.innerHTML = generateCheckboxesHtml(languages);
}

function addCoupons(data, add) {
  const cardsWrapper = document.querySelector(".cards-wrapper");

  function diffDate(endDate) {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0");
    const day = String(now.getDate()).padStart(2, "0");
    const date1 = new Date(`${year}-${month}-${day}`);
    const endDateDate = new Date(endDate);
    return (endDateDate - date1) / 1000 / 60 / 60 / 24;
  }

  if (data.length !== 0) {
    let content = "";
    data.forEach((item) => {
      content += `
        <div class="card">
          <img src="${item.image}">
          <div class="card-content">
            <div class="card-title">${item.name}</div>
            <div class="card-info-wrapper">
              <div class="card-info"><i class="fas fa-tags"></i>${item.category}</div>
              <div class="card-info"><i class="fas fa-language"></i>${item.language}</div>
              <div class="card-info"><i class="fas fa-star"></i>Rating: ${item.rating}</div>
              <div class="card-info"><i class="fas fa-film"></i>${item.lectures} Lectures</div>
              <div class="card-info"><i class="fas fa-user-graduate"></i>${item.students} Students</div>
            </div>
            <div class="card-price">
              <del>$${item.price}</del>
              <span>$${item.sale_price}</span>
            </div>
            <a href="${item.url}" class="card-button">Go to Course</a>
          </div>
        </div>
      `;
    });

    if (add) {
      cardsWrapper.innerHTML += content;
    } else {
      cardsWrapper.innerHTML = content;
    }
  } else {
    canLoad = false;
  }
}

function fetchData(endpoint, isFormSetup, add, requestBody, amount = true) {
  amount = amount ? "&amount=true" : "";
  console.log("Before fetch, offset:", offset); // Debug log
  return fetch(`${endpoint}?offset=${offset}${amount}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: requestBody,
  })
    .then((response) => response.json())
    .then((response) => {
      if (isFormSetup) {
        const languages = response.languages;
        const categories = response.categories;
        setupForm(languages, categories);
      }
      addCoupons(response.data, add);
      if ("total" in response) {
        amountEl.textContent = "Total found: " + response.total;
      }
      offset += 10; // Increment offset
      console.log("After fetch, offset:", offset); // Debug log
      canLoad = response.data.length !== 0;
      return true;
    })
    .catch((error) => {
      console.error("Fetch error:", error);
      canLoad = true;
      return false;
    });
}

window.addEventListener("scroll", () => {
  if (
    window.innerHeight + window.scrollY >= document.body.offsetHeight * 0.95 &&
    canLoad === true
  ) {
    canLoad = false;
    fetchData("search.php", false, true, getRequestBody());
  }
});

document.querySelector("#sendForm").addEventListener("click", (e) => {
  e.preventDefault();
  canLoad = true;
  offset = 0; // Reset offset
  fetchData("search.php", false, false, getRequestBody(), true);
});

document.addEventListener("DOMContentLoaded", () => {
  fetchData("api.php", true, true, getRequestBody());
});

const menuToggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".nav");

menuToggle.addEventListener("click", () => {
  nav.classList.toggle("active");
});
