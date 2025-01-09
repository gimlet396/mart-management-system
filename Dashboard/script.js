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
// Example function to filter sales by date range
function filterSales() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
  
    if (!startDate || !endDate) {
      alert('Please select a valid date range.');
      return;
    }
  
    alert(`Filtering sales from ${startDate} to ${endDate}`);
    // Here, you could make an API call or filter data dynamically
  }

  // Example function to search inventory items
function searchInventory() {
    const searchItem = document.getElementById('search-item').value.trim();
    
    if (!searchItem) {
      alert('Please enter an item name or ID.');
      return;
    }
  
    alert(`Searching for "${searchItem}" in inventory...`);
    // Here, you could filter the table dynamically or make an API call
  }
  // Example function to search customer records
function searchCustomer() {
    const searchCustomer = document.getElementById('search-customer').value.trim();
    
    if (!searchCustomer) {
      alert('Please enter a customer name or ID.');
      return;
    }
  
    alert(`Searching for customer "${searchCustomer}"...`);
    // You can implement further filtering or API calls here
  }
  