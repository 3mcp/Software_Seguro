console.log("Script agenda.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay"
    },
    locale: "pt-br",
    buttonText: {
      today: "Hoje",
      month: "Mês",
      week: "Semana",
      day: "Dia"
    },
    events: async function (fetchInfo, successCallback, failureCallback) {
      try {
        const res = await fetch("../application/Controllers/ConsultaController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ acao: "listar" })
        });

        const consultas = await res.json();
        console.log("Consultas carregadas:", consultas);


        const eventos = consultas.map(c => ({
          id: c.id,
          title: `${c.paciente} - ${c.medico}`,
          start: c.dataHora
        }));

        successCallback(eventos);
      } catch (err) {
        console.error("Erro ao carregar consultas:", err);
        failureCallback(err);
      }
    },
    eventClick: function (info) {
      window.location.href = `?pagina=consulta-detalhe&id=${info.event.id}`;
    }
  });

  calendar.render();
});
