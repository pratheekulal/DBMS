// document.addEventListener('DOMContentLoaded', function() {
//     loadProducts();
// });

// function loadProducts() {
//     const productSelects = document.querySelectorAll('select[name="product"]');
    
//     fetch('functions.php?action=get_products')
//         .then(response => response.json())
//         .then(data => {
//             productSelects.forEach(select => {
//                 data.products.forEach(product => {
//                     const option = document.createElement('option');
//                     option.value = product.id;
//                     option.textContent = product.name;
//                     select.appendChild(option);
//                 });
//             });
//         })
//         .catch(error => console.error('Error loading products:', error));
// }

// document.addEventListener('DOMContentLoaded', function() {
//     loadProducts();
//     loadReviews();
// });

// function loadProducts() {
//     const productSelects = document.querySelectorAll('select[name="product"]');
    
//     fetch('functions.php?action=get_products')
//         .then(response => response.json())
//         .then(data => {
//             productSelects.forEach(select => {
//                 data.products.forEach(product => {
//                     const option = document.createElement('option');
//                     option.value = product.id;
//                     option.textContent = product.name;
//                     select.appendChild(option);
//                 });
//             });
//         })
//         .catch(error => console.error('Error loading products:', error));
// }

// function loadReviews() {
//     const reviewsContainer = document.getElementById('reviews');
    
//     fetch('functions.php?action=get_reviews')
//         .then(response => response.json())
//         .then(data => {
//             data.reviews.forEach(review => {
//                 const reviewDiv = document.createElement('div');
//                 reviewDiv.className = 'review';
//                 reviewDiv.innerHTML = `
//                     <h3>${review.username}</h3>
//                     <p>Rating: ${review.rating}</p>
//                     <p>${review.text}</p>
//                     ${review.image ? `<img src="${review.image}" alt="Review Image">` : ''}
//                 `;
//                 reviewsContainer.appendChild(reviewDiv);
//             });
//         })
//         .catch(error => console.error('Error loading reviews:', error));
// }


document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

function loadProducts() {
    const productSelects = document.querySelectorAll('select[name="product"]');
    
    fetch('functions.php?action=get_products')
        .then(response => response.json())
        .then(data => {
            productSelects.forEach(select => {
                select.innerHTML = ''; // Clear existing options
                data.products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    select.appendChild(option);
                });
            });
        })
        .catch(error => console.error('Error loading products:', error));
}
