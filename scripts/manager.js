/**
 * Author: Chong Yi Yang
 * Target: manager.php
 * Purpose: This file is used to add interactivity to the manager.php page
 * Created: 15/10/2024
 * Last updated: 19/10/2024
 * Credits: 
 */

"use strict";

window.onload = () => {
    const table = document.querySelector('.report-table');
    if (table) {
        // Get all expand buttons
        const buttons = table.querySelectorAll('.expand-btn');
        buttons.forEach(button => {
            // Add expand on click event listener to each button
            const id = button.getAttribute("data-id");
            const detailsRow = document.getElementById("order-" + id);
            button.onclick = () => {
                
                const isVisible = detailsRow.style.display === 'table-row';

                detailsRow.style.display = isVisible ? 'none' : 'table-row';
                button.querySelector('.expand-icon').innerHTML = isVisible ? '&#x25BC;' : '&#x25B2;';
            };


            const deleteForm = detailsRow.querySelector(".delete-order-form");

            if (deleteForm) {
                deleteForm.onsubmit = () => confirm("Are you sure you want to delete this order?");
            }
        });
    }
}