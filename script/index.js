const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');

const darkMode = document.querySelector('.dark-mode');


menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
})


Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
        <td>${order.UserID}</td>
        <td>${order.UserName}</td>
        <td>${order.Email}</td>
        <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
        <td class="primary">Details</td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});






// const sideMenu = document.querySelector('aside');
// const menuBtn = document.getElementById('menu-btn');
// const closeBtn = document.getElementById('close-btn');
// const darkMode = document.querySelector('.dark-mode');
// const darkModeIcons = darkMode.querySelectorAll('span.material-icons-sharp');


// function enableDarkMode() {
//   document.body.classList.add('dark-mode-variables');
//   darkModeIcons[0].classList.remove('active');
//   darkModeIcons[1].classList.add('active');
// }


// function enableLightMode() {
//   document.body.classList.remove('dark-mode-variables');
//   darkModeIcons[0].classList.add('active');
//   darkModeIcons[1].classList.remove('active');
// }


// const shouldEnableDarkModeByDefault = true; 

// if (shouldEnableDarkModeByDefault) {
//   enableDarkMode();
// } else {
//   enableLightMode();
// }

// menuBtn.addEventListener('click', () => {
//   sideMenu.style.display = 'block';
// });

// closeBtn.addEventListener('click', () => {
//   sideMenu.style.display = 'none';
// });

// darkMode.addEventListener('click', () => {
//   if (document.body.classList.contains('dark-mode-variables')) {
//     enableLightMode();
//   } else {
//     enableDarkMode();
//   }
// });

// Orders.forEach(order => {
//     const tr = document.createElement('tr');
//     const trContent = `
//         <td>${order.UserID}</td>
//         <td>${order.UserName}</td>
//         <td>${order.Email}</td>
//         <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
//         <td class="primary">Details</td>
//     `;
//     tr.innerHTML = trContent;
//     document.querySelector('table tbody').appendChild(tr);
// });

