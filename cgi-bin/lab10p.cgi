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
        body {{
            text-align: center;
        }}
        h1 {{
            background-color: #FF5733;
            color: white;
            font-size: 48px;
            padding: 20px;
        }}
        img {{
            width: 80%;
            height: auto;
            border: 15px solid #900C3F;
        }}
    </style>
</head>
<body>
    <h1>{city}, {country}</h1>
    <img src="{image_url}" alt="Image of {city}">
</body>
</html>
""".format(
    city=city,
    country=country,
    image_url=image_url
)
