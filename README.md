# Group-project-PHP
A series of PHP web pages written over a week as part of my contribution to a computer science group project
Use PHP in order to design a website that could be used by hospital staff and patients.

Currently working functionality:
Login functionality with session usage to control access level.
Database passwords saved as hash values to prevent tampering and ensure security.
Appointment booking for patients.
Doctors in booking option menu are populated according to values in database.
Allows a user with the correct access (Receptionist)
to first search for a patient according to ID, and then view their names. Then the user may book a
appointment for a patient.
A patient may view all their active appointments on the appointment page.
Any user may edit their own personal information so that it is up to date.
A user with the correct access level (admin) may edit and view the personal information of other users 
such as their home address or phone number by first searching their user ID.

Not working :
Not currently able to generate a medical report for a patient as requested by a doctor.
