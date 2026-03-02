<p>Name: Aaron Antonio Aceves Alvarez</p>
<p>Role: Senior Engineer / Tech Lead - PHP Role </p>
<p>Client: TrimRX </p>
 

<h2>Event fields </h2>
 - Required 
    - Event name
    - Date
    - Start time
    - End time
    - Location
    - Description
    - Reservation required
- Optional
    - Capacity
    - Price
    - Additional Info
  
<h2>Events Source Format</h2>

 - Global Events
     - Data
         - Event
             - Event Name
             - Location
             - Description
         - Schedule
             - Date
             - Start time
             - End time
         - Prices
             - Reservation Required
             - Price
         - Information
             - Capacity
             - Additional Info
 - Events Mania
     - Data
         - Event
             - Event Name
             - Location
             - Date
             - Start time
             - End time
         - Tickets
             - Reservation required
             - Price
             - Capacity
         - Information
             - Description
             - Additional information
 - Fast Event
     - Data
         - Event
             - Event name
             - Date
             - Start time
             - End time
             - Location
             - Description
             - Reservation
             - Price
 - Database
     - Tables
         - Events
             - Event_id
             - Event name
         - Event_schedules
             - id
             - Event_id
             - Event_Date
             - Start time
             - End time
         - Event_locations
             - id
             - Event_id
             - Location
         - Event_prices
             - id
             - Event_id
             - Price
             - Reservation required
             - Capacity
         - Event_information
             - id
             - Event_id
             - Description
             - Additional Info 

