SELECT * FROM transport
LEFT JOIN booking 
ON booking.transport_id=transport.transport_id 
WHERE transport.transport_type = "student" 
UNION
SELECT * FROM transport
RIGHT JOIN booking
ON booking.transport_id=transport.transport_id
WHERE transport.transport_type = "student" 
ORDER BY transport_no ASC