document.addEventListener("DOMContentLoaded", function () {
  let isStartDateSelected = false;
  let startDate = "";
  const calendarEl = document.getElementById("calendar1");
  const dateStartInput = document.getElementById("date_start");
  const dateEndInput = document.getElementById("date_end");
  const numberOfDaysElement = document.getElementById("nombre_jours");
  const duration = document.getElementById("duration");
  const resas = JSON.parse(
    document.getElementById("resas").getAttribute("data-list")
  );

  console.log(resas);
  // //////////
  // Gestion des réservations placées en événements

  // Transformation des réservations en un format compatible avec FullCalendar
  let events = resas.map(function (resa) {
    // condition pour la couleur de fond des réservations en fonction de la confirmation ou non

    if (resa.confirmation == true) {
      // si la réservation est confirmée
      return {
        title: "Réservation", // Titre de l'événement
        start: resa.start, // Date de début de la réservation
        end: resa.end,
        allDay: true,
        display: "background", // Date de fin de la réservation
        backgroundColor: "red", // Couleur de fond de l'événement (peut être personnalisée)
      };
    } else {
      // si la réservation n'est pas confirmée
      return {
        title: "Réservation", // Titre de l'événement
        start: resa.start, // Date de début de la réservation
        end: resa.end,
        allDay: true,
        display: "background", // détermine le fait que l'événement soit placé en couleur
        backgroundColor: "orange", // Couleur de fond de l'événement (peut être personnalisée)
      };
    }
  });

  function updateNumberOfDays() {
    const dateStart = new Date(startDate);
    const dateEnd = new Date(dateEndInput.value);

    let numberOfDays = 0;
    let currentDate = dateStart;

    while (currentDate <= dateEnd) {
      // Vérifier si le jour de la semaine n'est pas un samedi (6) ou un dimanche (0)
      if (currentDate.getDay() !== 6 && currentDate.getDay() !== 0) {
        numberOfDays++;
      }

      currentDate.setDate(currentDate.getDate() + 1);
    }

    // Afficher le nombre de jours entre les deux dates dans l'élément #nombre_jours
    duration.value = numberOfDays;
    console.log(duration);
    numberOfDaysElement.textContent = numberOfDays;
  }
let today = new Date();
  const calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: "bootstrap5",
    initialView: "dayGridMonth",
    timeZone: "Europe/Paris",
    locale: "fr",
    firstDay: 1,
    validRange: {
      start: today,
      
    },
    selectable: true,
    businessHours: [
      {
        // specify an array instead
        daysOfWeek: [1, 2, 3, 4, 5], //
      },
    ],
    events: events,
    // selectOverlap empêche la sélection de jours déjà réservés pour les éléments placés en background
    selectOverlap: function (event) {
      return event.rendering === "background";
    },
    headerToolbar: {
      left: "prev,next",
      center: "title",
      right: "prevYear,nextYear",
    },
    select: function (info) {
      if (!isStartDateSelected) {
        startDate = info.startStr;
        dateStartInput.value = startDate;
        isStartDateSelected = true;
      } else {
        const endDate = info.startStr;
        dateEndInput.value = endDate;
        isStartDateSelected = false;
        updateNumberOfDays(); // Met à jour le nombre de jours quand date_end est sélectionné
      }
    },
  });

  // Écoutez l'événement 'input' pour les champs de date
  dateStartInput.addEventListener("input", function () {
    startDate = dateStartInput.value;
    updateNumberOfDays(); // Met à jour le nombre de jours dès que date_start est modifié
  });

  dateEndInput.addEventListener("input", function () {
    updateNumberOfDays(); // Met à jour le nombre de jours dès que date_end est modifié
  });

  calendar.render();
});
