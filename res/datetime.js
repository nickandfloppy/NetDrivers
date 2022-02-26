<!-- Hide from older browsers
now = new Date();
day = new Object();
month = new Object();
day[0] = "Sunday";
day[1] = "Monday";
day[2] = "Tuesday";
day[3] = "Wednesday";
day[4] = "Thursday";
day[5] = "Friday";
day[6] = "Saturday";
month[0] = "Jan.";
month[1] = "Feb.";
month[2] = "March";
month[3] = "April";
month[4] = "May";
month[5] = "June";
month[6] = "July";
month[7] = "Aug.";
month[8] = "Sept.";
month[9] = "Oct.";
month[10] = "Nov.";
month[11] = "Dec.";
year = now.getYear();
if (year < 1000) year += 1900;

document.write(
  "<br>" +
    day[now.getDay()] +
    ", " +
    month[now.getMonth()] +
    " " +
    now.getDate() +
    ", " +
    year
);

// Stop Hiding From Old Browsers -->