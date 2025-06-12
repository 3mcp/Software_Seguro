document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("agenda");
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        locale: "pt-br",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay"
        },
        buttonText: {
            today: "Hoje",
            month: "Mês",
            week: "Semana",
            day: "Dia"
        },
        events: async (fetchInfo, successCallback, failureCallback) => {
            try {
                const resp = await fetch("/Software_Seguro/application/index.php?action=listarConsultas");
                const data = await resp.json();

                if (!data.success) throw new Error(data.message);

                const eventos = data.data.map(consulta => ({
                    id: consulta.id,
                    title: `${sanitize(consulta.nomePaciente)} - ${sanitize(consulta.nomeMedico)} (${sanitize(consulta.nomeEspecialidade)})`,
                    start: consulta.dataHora,
                    allDay: false,
                    extendedProps: {
                        pacienteId: consulta.pacienteId,
                        medicoId: consulta.medicoId,
                        especialidadeId: consulta.especialidadeId
                    }
                }));

                successCallback(eventos);
            } catch (err) {
                console.error("Erro ao carregar consultas:", err);
                failureCallback("Erro ao carregar eventos");
            }
        },
        dateClick(info) {
            const dataSelecionada = info.dateStr;
            window.location.href = `/Software_Seguro/application/index.php?pagina=consulta-detalhe&data=${dataSelecionada}`;
        },
        eventClick(info) {
            const idConsulta = info.event.id;
            window.location.href = `/Software_Seguro/application/index.php?pagina=consulta-detalhe&id=${idConsulta}`;
        }
    });

    calendar.render();
});

function sanitize(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? '';
    return div.innerHTML;
}
