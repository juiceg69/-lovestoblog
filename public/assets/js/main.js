  const navItems = document.querySelector('.nav__items');
  const toggleNavBtn = document.querySelector('#toggle__nav-btn');

  // Toggle nav dropdown
  const toggleNav = () => {
    const isNavOpen = navItems.style.display === 'flex';
    if (isNavOpen) {
      navItems.style.display = '';
      toggleNavBtn.innerHTML = '<i class="uil uil-bars"></i>'; 
    } else {
      navItems.style.display = 'flex';
      toggleNavBtn.innerHTML = '<i class="uil uil-multiply"></i>'; 
    }
  };
  toggleNavBtn.addEventListener('click', toggleNav);

  
// DASHBOARD
const sidebar = document.querySelector('aside');
const showSidebarBtn = document.querySelector('#show__sidebar-btn');
const hideSidebarBtn = document.querySelector('#hide__sidebar-btn');


const showSidebar = () => {
  sidebar.style.left = '0'; 
  showSidebarBtn.style.display = 'none'; 
  hideSidebarBtn.style.display = 'inline-block'; 
}


const hideSidebar = () => {
  sidebar.style.left = '-100%';
  showSidebarBtn.style.display = 'inline-block'; 
  hideSidebarBtn.style.display = 'none';
}


showSidebarBtn.addEventListener('click', showSidebar);
hideSidebarBtn.addEventListener('click', hideSidebar);

// Manejar la visibilidad del sidebar al cambiar el tamaño de la ventana
window.addEventListener('resize', () => {
  if (window.innerWidth >= 600) {
    sidebar.style.left = '0'; 
    hideSidebarBtn.style.display = 'none'; 
    showSidebarBtn.style.display = 'none'; 
  } else {
    hideSidebar();
  }
});

// Manejar la visibilidad del sidebar al cambiar el tamaño de la ventana
window.addEventListener('resize', () => {
    if (window.innerWidth >= 600) {
        sidebar.classList.remove('show');
        sidebar.style.left = ''; // Restablece al diseño de cuadrícula
        showSidebarBtn.style.display = 'none';
        hideSidebarBtn.style.display = 'none';
    } else if (!sidebar.classList.contains('show')) {
        hideSidebar();
    }
});


    // Función para mostrar/ocultar la contraseña
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.querySelector('#lock-icon i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function togglePasswordVisibility(inputId, iconElement) {
      const input = document.getElementById(inputId);
      if (input.type === "password") {
          input.type = "text";
          iconElement.classList.add("active"); // Agregar una clase para cambiar el color
      } else {
          input.type = "password";
          iconElement.classList.remove("active"); // Quitar la clase
      }
  }

    // Función para cambiar el ícono del candado al enviar el formulario
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const lockIcon = document.querySelector('#lock-icon i');

        form.addEventListener('submit', (event) => {
            // Simular validación (en producción, esto se maneja en el backend)
            const password = document.getElementById('password').value;

            if (password === 'correctpassword') { // Reemplaza 'correctpassword' con la lógica real
                lockIcon.classList.remove('fa-lock');
                lockIcon.classList.add('fa-unlock');
                lockIcon.style.color = 'green'; // Cambiar color a verde para indicar éxito
            } else {
                lockIcon.classList.remove('fa-unlock');
                lockIcon.classList.add('fa-lock');
                lockIcon.style.color = 'red'; // Cambiar color a rojo para indicar error
            }
        });
    });


  // MANAGE-USER
  setTimeout(function() {
    $('.alert').alert('close');
  }, 5000); 

  // Función para confirmar la eliminación y mostrar un mensaje
  function togglePasswordVisibility(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        input.type = "password";
        icon.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// Profile para mostrar password
function togglePasswordVisibility(inputId, icon) {
  const input = document.getElementById(inputId);
  if (input.type === "password") {
      input.type = "text";
      icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
  } else {
      input.type = "password";
      icon.innerHTML = '<i class="fas fa-eye"></i>';
  }
}

