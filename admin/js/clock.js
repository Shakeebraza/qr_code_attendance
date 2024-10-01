
  function updateClock() {
            // Get the time in America/Monterrey time zone
            const options = { timeZone: 'America/Monterrey', hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const timeString = new Date().toLocaleTimeString('en-US', options);

            document.getElementById('clock').textContent = timeString;
        }

        // Initial call
        updateClock();

        // Update the clock every second
        setInterval(updateClock, 1000);
