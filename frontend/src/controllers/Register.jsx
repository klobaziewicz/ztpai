import { useState } from 'react';
import Nav from "./Nav";

function Register() {
    const [formData, setFormData] = useState({
        email: '',
        name: '',
        nick: '',
        password: '',
    });
    const [registering, setRegistering] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setRegistering(true);
        setError(null);
        setSuccess(false);

        try {
            const response = await fetch('http://localhost:8000/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.error || "Unknown error");
            }

            await response.json();
            setSuccess(true);
            setFormData({ email: '', name: '', nick: '', password: '' });
        } catch (error) {
            console.error('Register error:', error);
            setError(error.message);
        } finally {
            setRegistering(false);
        }
    };

    return (
        <div>
            <Nav/>
            <form onSubmit={handleSubmit}>
                <h2>Register</h2>
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value={formData.email}
                    onChange={handleChange}
                    required
                />
                <input
                    type="name"
                    name="name"
                    placeholder="Name"
                    value={formData.name}
                    onChange={handleChange}
                    required
                />
                <input
                    type="name"
                    name="nick"
                    placeholder="Nick"
                    value={formData.nick}
                    onChange={handleChange}
                    required
                />
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    value={formData.password}
                    onChange={handleChange}
                    required
                />
                <button type="submit" disabled={registering}>
                    {registering ? 'Registering...' : 'Register'}
                </button>
            </form>

            {error && <p style={{color: 'red'}}>{error}</p>}
            {success && <p style={{color: 'green'}}>Registered successfully!</p>}
        </div>
    );
}

export default Register;
