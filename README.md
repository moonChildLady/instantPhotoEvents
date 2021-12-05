# Associations Now

### Introduction
A web-based platform for attendees of an event to access the photos taken by photographers in real-time by simply scanning a QR code.

### Features
1. Event management (create a specify URL and QR code for the event host)
2. Private (need passcode) or public events
3. Real-time of the photo album with "Like" button
4. Display the most "Like" photo on the dashborad
5. Multiple albums on the same event

### Reqiurement
* PHP version 5.7 or above (Yii freamwork)
* MySQL 5.6 or above
* nodejs 
1. websocket.io (for real-time process)
2. chokidar (for trigger folder changing callback)

### Usage
#### node.js
`node album2.js`

### Follow up/Improvment
In this project, the operator upload the photos manually on the platform. In the feature, the photographers take the photos and the photos will automatically upload to the platform.
1. The Wi-Fi SD Card for the wireless transmission.
2. Build the connector that connect SD card storage to cloud directory.
