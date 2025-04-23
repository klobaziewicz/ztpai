import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

const UsersList = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(false);

    const fetchUsers = () => {
        setLoading(true);
        fetch('http://localhost:8000/api/users')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => setUsers(data))
            .catch(error => console.error('Fetch error:', error))
            .finally(() => setLoading(false));
    };

    useEffect(() => {
        fetchUsers();
    }, []);

    return (
        <div>
            <h2>Users List</h2>
            <button onClick={fetchUsers} disabled={loading}>
                {loading ? "Loading..." : "Refresh"}
            </button>
            <ul>
                {users.map(user => (
                    <li key={user.id}>
                        <Link to={`/users/${user.id}`}>
                            <strong>{user.name}</strong> - {user.email}
                        </Link>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default UsersList;
