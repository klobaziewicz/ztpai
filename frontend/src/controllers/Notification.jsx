import { useEffect, useState } from "react";
import Nav from "./Nav";

function Notification() {
    const [notifications, setNotifications] = useState([]);

    useEffect(() => {
        const fetchNotifications = async () => {
            const token=localStorage.getItem('token');
            try {
                const response = await fetch("http://localhost:8000/api/notifications", {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                },);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                setNotifications(data);
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        };

        fetchNotifications();
    }, []);

    return (
        <div>
            <Nav/>
            <h2>Powiadomienia</h2>
            <ul>
                {notifications.map((notif, idx) => (
                    <li key={idx}>
                        <strong>{notif.from_user}</strong> polubił post
                        użytkownika <strong>{notif.to_user}</strong> (Post ID: {notif.post_id})
                        <br/>
                        <small>{notif.created_at}</small>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default Notification;