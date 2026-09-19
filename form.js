const contactForm = document.getElementById('contact-form');
const contactSubmit = document.getElementById('contact-submit');
const formStatus = document.getElementById('form-status');

if (contactForm && contactSubmit && formStatus) {
  contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const originalLabel = contactSubmit.textContent;
    contactSubmit.disabled = true;
    contactSubmit.textContent = 'ENVIANDO CONSULTA…';
    contactForm.setAttribute('aria-busy', 'true');
    formStatus.hidden = false;
    formStatus.className = 'form-status is-pending';
    formStatus.setAttribute('role', 'status');
    formStatus.textContent = 'Estamos enviando tu consulta de forma segura…';

    try {
      const formData = new FormData(contactForm);
      const payload = JSON.stringify(Object.fromEntries(formData));
      const response = await fetch(contactForm.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json'
        },
        body: payload
      });
      const result = await response.json();

      if (!response.ok || result.success === false) {
        throw new Error(result.message || 'No pudimos enviar la consulta.');
      }

      contactForm.reset();
      formStatus.className = 'form-status is-success';
      formStatus.setAttribute('role', 'status');
      formStatus.innerHTML = '<strong>Consulta recibida correctamente.</strong><span>Analizaremos tu proyecto y Manuel Conde responderá dentro de las próximas 24 a 48 horas.</span>';
      formStatus.focus();
    } catch (error) {
      formStatus.className = 'form-status is-error';
      formStatus.setAttribute('role', 'alert');
      formStatus.innerHTML = '<strong>No pudimos completar el envío.</strong><span>Revisá tu conexión e intentá nuevamente. Si el problema continúa, escribinos a <a href="mailto:info@condegraphics.com">info@condegraphics.com</a>.</span>';
      formStatus.focus();
    } finally {
      contactSubmit.disabled = false;
      contactSubmit.textContent = originalLabel;
      contactForm.removeAttribute('aria-busy');
    }
  });
}
