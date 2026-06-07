// Main JavaScript for Hostel Booking System

document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    setActiveNavLink();
    setupEventListeners();
}

// Set active navigation link
function setActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(currentPage)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Setup event listeners
function setupEventListeners() {
    // Form submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Modal close
    const closeButtons = document.querySelectorAll('.close-modal');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
}

// Form validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });

    return isValid;
}

// Show alert
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Open modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-TZ', {
        style: 'currency',
        currency: 'TZS'
    }).format(amount);
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-TZ', options);
}

// API call helper
async function apiCall(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('API call failed:', error);
        showAlert('An error occurred. Please try again.', 'danger');
        return null;
    }
}

// Search functionality
function searchHostels(query) {
    const hostelCards = document.querySelectorAll('.hostel-card');
    
    hostelCards.forEach(card => {
        const name = card.querySelector('.hostel-name')?.textContent.toLowerCase() || '';
        if (name.includes(query.toLowerCase())) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Filter hostels by gender
function filterByGender(gender) {
    const hostelCards = document.querySelectorAll('.hostel-card');
    
    hostelCards.forEach(card => {
        const cardGender = card.getAttribute('data-gender');
        if (gender === 'all' || cardGender === gender) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Sort hostels
function sortHostels(sortBy) {
    const hostelList = document.querySelector('.hostel-list');
    const cards = Array.from(hostelList.querySelectorAll('.hostel-card'));

    cards.sort((a, b) => {
        let aValue, bValue;

        switch(sortBy) {
            case 'name':
                aValue = a.querySelector('.hostel-name')?.textContent || '';
                bValue = b.querySelector('.hostel-name')?.textContent || '';
                return aValue.localeCompare(bValue);
            
            case 'available_desc':
                aValue = parseInt(a.getAttribute('data-available') || 0);
                bValue = parseInt(b.getAttribute('data-available') || 0);
                return bValue - aValue;
            
            case 'available_asc':
                aValue = parseInt(a.getAttribute('data-available') || 0);
                bValue = parseInt(b.getAttribute('data-available') || 0);
                return aValue - bValue;
            
            default:
                return 0;
        }
    });

    cards.forEach(card => {
        hostelList.appendChild(card);
    });
}

// Print functionality
function printContent(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(element.innerHTML);
        printWindow.document.close();
        printWindow.print();
    }
}

// Export table to CSV
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];
        
        cols.forEach(col => {
            csvRow.push(col.textContent.trim());
        });
        
        csv.push(csvRow.join(','));
    });

    downloadCSV(csv.join('\n'), filename);
}

// Download CSV
function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('visible');
    }
}

// Check if user is logged in
function isLoggedIn() {
    // This would typically check a session variable
    return document.body.getAttribute('data-user-id') !== null;
}

// Logout
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '../auth/logout.php';
    }
}

// Load more functionality (for pagination)
function loadMore(page) {
    const url = window.location.pathname + '?page=' + page;
    apiCall(url).then(data => {
        if (data && data.html) {
            document.querySelector('.items-container').insertAdjacentHTML('beforeend', data.html);
        }
    });
}

// Animate elements on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    });

    elements.forEach(el => observer.observe(el));
}

// Initialize tooltips
function initializeTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
            tooltip.style.left = (rect.left + (rect.width - tooltip.offsetWidth) / 2) + 'px';
        });

        element.addEventListener('mouseleave', function() {
            const tooltips = document.querySelectorAll('.tooltip');
            tooltips.forEach(t => t.remove());
        });
    });
}

// Call animations on page load
window.addEventListener('load', function() {
    animateOnScroll();
    initializeTooltips();
});
