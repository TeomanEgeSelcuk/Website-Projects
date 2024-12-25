#!/usr/bin/env python2

import cgi
import cgitb

cgitb.enable()  # Enables debugging mode

# Output HTTP header
print "Content-Type: text/html\n"

# Create a FieldStorage object to parse the form data
form = cgi.FieldStorage()

# Retrieve form data and convert to uppercase
city = form.getvalue('city', '').upper()
province = form.getvalue('province', '').upper()
country = form.getvalue('country', '').upper()
image_url = form.getvalue('image_url', '')

# Escape HTML special characters to prevent injection
city = cgi.escape(city)
province = cgi.escape(province)
country = cgi.escape(country)
image_url = cgi.escape(image_url)

# Output the HTML page
print """<!DOCTYPE html>
<html>
<head>
    <title>{city}, {country}</title>
    <style>
        html, body {{
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #FF5733; /* Bright red background */
        }}
        body {{
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }}
        h1 {{
            background-color: #FF5733; /* Red background to match the page */
            color: yellow;
            font-size: 48px;
            padding: 20px;
            margin: 0;
            width: 100%;
        }}
        .image-container {{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #FF5733; /* Ensure background around the image is red */
            padding: 20px 0;
        }}
        img {{
            width: 80%;
            height: auto;
            border: 15px solid #900C3F; /* Wide maroon-colored border */
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.5); /* Adds shadow for attractiveness */
        }}
    </style>
</head>
<body>
    <h1>{city}, {country}</h1>
    <div class="image-container">
        <img src="{image_url}" alt="Image of {city}">
    </div>
</body>
</html>
""".format(
    city=city,
    country=country,
    image_url=image_url
)
