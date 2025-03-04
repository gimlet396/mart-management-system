// Example: Creating a bar chart with Chart.js
const ctx = document.getElementById('salesChart').getContext('2d');

const salesChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
    datasets: [{
      label: 'Monthly Sales ($)',
      data: [12000, 15000, 8000, 18000, 20000, 17000],
      backgroundColor: [
        '#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#f1c40f', '#e74c3c'
      ],
      borderColor: '#34495e',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".delete-button").forEach(button => {
      button.addEventListener("click", function () {
          const productId = this.getAttribute("onclick").match(/\d+/g)[0]; // Extract productId
          const stock = this.getAttribute("onclick").match(/\d+/g)[1]; // Extract stock

          deleteProduct(productId, stock);
      });
  });
});

function deleteProduct(productId, availableQuantity) {
  let quantityToDelete = prompt(`Enter quantity to delete (Available: ${availableQuantity}):`, 1);

  if (quantityToDelete !== null) {
      quantityToDelete = parseInt(quantityToDelete);

      if (isNaN(quantityToDelete) || quantityToDelete <= 0 || quantityToDelete > availableQuantity) {
          alert("Invalid quantity. Please enter a valid number.");
          return;
      }

      if (confirm(`Are you sure you want to delete ${quantityToDelete} items?`)) {
          fetch('delete_product.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ productId: productId, quantity: quantityToDelete })
          })
          .then(response => response.json())
          .then(data => {
              alert(data.message);
              if (data.success) {
                  location.reload(); // Refresh page after deletion
              }
          })
          .catch(error => console.error("Error:", error));
      }
  }
}


  