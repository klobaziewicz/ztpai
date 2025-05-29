import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

const UserDetail = () => {
    const { nick } = useParams();  // Pobieramy nick z URL
    const [user, setUser] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem('token');
        fetch(`http://localhost:8000/api/user/${nick}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('User not found');
                }
                return response.json();
            })
            .then(data => setUser(data))
            .catch(error => console.error('Fetch error:', error));
    }, [nick]);

    if (!user) return <p>Loading user details...</p>;

    return (
        <div>
            <h2>{user.name}</h2>
            <p>Email: {user.email}</p>
        </div>
    );
};

export default UserDetail;
