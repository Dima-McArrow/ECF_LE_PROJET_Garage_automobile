document.addEventListener("DOMContentLoaded", function () {
  fetchOpeningHours()

  const pollingInterval = 60000; // 1 minute
  setInterval(fetchOpeningHours, pollingInterval)
});

function fetchOpeningHours() {
  fetch("get_hours.php")
    .then((response) => response.json())
    .then((data) => {
      displayOpeningHours(data)
    })
    .catch((error) => {
      console.error("Error fetching opening hours:", error)
    })
}

function displayOpeningHours(openingHours) {
  const openingHoursDiv = document.getElementById("openingHours")
  if (!openingHoursDiv) return

  let html =
    '<p class="nav_font text-center">Horaires d\'ouverture</p><ul class="card_body_font text-start" id="opening_hours">'

  openingHours.forEach((entry) => {
    const dayOfWeek = getDayOfWeek(entry.day_of_week)

    let isClosed =
      (entry.opening_time_am === "Closed" ||
        entry.opening_time_am.startsWith("00:00")) &&
      (entry.closing_time_am === "Closed" ||
        entry.closing_time_am.startsWith("00:00")) &&
      (entry.opening_time_pm === "Closed" ||
        entry.opening_time_pm.startsWith("00:00")) &&
      (entry.closing_time_pm === "Closed" ||
        entry.closing_time_pm.startsWith("00:00"))

    let morningSession =
      entry.opening_time_am !== "Closed" &&
      !entry.opening_time_am.startsWith("00:00")
        ? `${entry.opening_time_am} - ${entry.closing_time_am}`
        : ""
    let afternoonSession =
      entry.opening_time_pm !== "Closed" &&
      !entry.opening_time_pm.startsWith("00:00")
        ? `${entry.opening_time_pm} - ${entry.closing_time_pm}`
        : ""

    let sessions = [morningSession, afternoonSession].filter(
      (session) => session !== ""
    )
    let displayLine = isClosed
      ? '<span class="closed_sunday">Fermé</span>'
      : sessions.join(", ")

    html += `<li><b>${dayOfWeek}</b>: ${displayLine}</li>`
  })

  html += "</ul>"
  openingHoursDiv.innerHTML = html
}

function getDayOfWeek(number) {
  const daysOfWeek = [
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
    "Dimanche",
  ]
  return daysOfWeek[number - 1]
}
