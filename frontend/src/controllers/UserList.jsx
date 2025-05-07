import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

const UsersList = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(false);
    const [formData, setFormData] = useState({
        name: '',
        nick: '',
        email: '',
        password: '',
    });
    const [creating, setCreating] = useState(false);
    const [error, setError] = useState(null);

    const fetchUsers = () => {
        setLoading(true);
        const token=localStorage.getItem('token');
        fetch('http://localhost:8000/api/users',{
                headers: {
                    'Content-type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                }
            })
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
    
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prevState => ({
            ...prevState,
            [name]: value,
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setCreating(true);
        setError(null);

        fetch('http://localhost:8000/api/createUser', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(formData),
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || "Unknown error") });
                }
                return response.json();
            })
            .then(() => {
                setFormData({ name: '', nick: '', email: '', password: '' });
                fetchUsers();
            })
            .catch(error => {
                console.error('Create user error:', error);
                setError(error.message);
            })
            .finally(() => setCreating(false));
    };

    return (
        <div>
            <h2>Users List</h2>
            <button onClick={fetchUsers} disabled={loading}>
                {loading ? "Loading..." : "Refresh"}
            </button>
            <ul>
                {users.map(user => (
                    <li key={user.id}>
                        <Link to={`/user/${user.nick}`}>
                            <strong>{user.name}</strong> - {user.email}
                        </Link>
                    </li>
                ))}
            </ul>

            <h2>Create New User</h2>
            <form onSubmit={handleSubmit} style={{maxWidth: '400px', marginTop: '20px'}}>
                <div>
                    <label>Name:</label>
                    <input
                        type="text"
                        name="name"
                        value={formData.name}
                        onChange={handleInputChange}
                        required
                    />
                </div>
                <div>
                    <label>Nick:</label>
                    <input
                        type="text"
                        name="nick"
                        value={formData.nick}
                        onChange={handleInputChange}
                        required
                    />
                </div>
                <div>
                    <label>Email:</label>
                    <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleInputChange}
                        required
                    />
                </div>
                <div>
                    <label>Password:</label>
                    <input
                        type="password"
                        name="password"
                        value={formData.password}
                        onChange={handleInputChange}
                        required
                    />
                </div>

                <button type="submit" disabled={creating}>
                    {creating ? "Creating..." : "Create User"}
                </button>

                {error && <p style={{color: 'red'}}>Error: {error}</p>}
            </form>
        </div>
    );
};

export default UsersList;
