(function () {
  const ctxPostulantesPorArea = document.getElementById('postulantesPorArea');
  const ctxCitasPorFecha = document.getElementById('citasPorFecha');
  const ctxEstadoEntrevistas = document.getElementById('estadoEntrevistas');

  if (ctxPostulantesPorArea && ctxCitasPorFecha && ctxEstadoEntrevistas) {
      const postulantesPorArea = JSON.parse(ctxPostulantesPorArea.dataset.postulantesPorArea);
      const citasPorFecha = JSON.parse(ctxCitasPorFecha.dataset.citasPorFecha);
      const estadoEntrevistas = JSON.parse(ctxEstadoEntrevistas.dataset.estadoEntrevistas);

      new Chart(ctxPostulantesPorArea, {
          type: 'pie',
          data: {
              labels: Object.keys(postulantesPorArea),
              datasets: [{
                  data: Object.values(postulantesPorArea),
                  backgroundColor: [
                      '#FF6384',
                      '#36A2EB',
                      '#FFCE56',
                      '#4BC0C0',
                      '#9966FF',
                      '#FF9F40'
                  ]
              }]
          }
      });

      new Chart(ctxCitasPorFecha, {
          type: 'bar',
          data: {
              labels: Object.keys(citasPorFecha),
              datasets: [{
                  data: Object.values(citasPorFecha),
                  backgroundColor: '#36A2EB'
              }]
          }
      });

      new Chart(ctxEstadoEntrevistas, {
          type: 'doughnut',
          data: {
              labels: Object.keys(estadoEntrevistas),
              datasets: [{
                  data: Object.values(estadoEntrevistas),
                  backgroundColor: [
                      '#FF6384',
                      '#36A2EB',
                      '#FFCE56'
                  ]
              }]
          }
      });
  } else {
      console.error('Algún elemento del gráfico no fue encontrado.');
  }
})();
