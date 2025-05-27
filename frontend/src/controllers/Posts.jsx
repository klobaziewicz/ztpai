import React, { useEffect, useState } from "react";
import Nav from "./Nav";
import '../style/posts.css';
import {Link} from "react-router-dom";

function Posts() {
    const [posts, setPosts] = useState([]);
    const [loadingPost, setLoadingPost] = useState(false);
    const [loadingLike, setLoadingLike] = useState(false);
    const [error, setError] = useState(null);
    const [errorCreate, setErrorCreate] = useState(null);
    const [creating, setCreating] = useState(false);
    const [formData, setFormData] = useState({
        title: '',
        content: '',
    });

    const fetchPosts = () => {
        setLoadingPost(true);
        const token=localStorage.getItem('token');
        fetch('http://localhost:8000/api/posts',{
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
            .then(data => setPosts(data))
            .catch(error => console.error('Fetch error:', error))
            .finally(() => setLoadingPost(false));
    };

    const polub = (post_id) => {
        setLoadingLike(true);
        const token=localStorage.getItem('token');

        fetch('http://localhost:8000/api/likePost', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({"post_id": post_id}),
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || "Unknown error") });
                }
                return response.json();
            })
            .then(() => {
                fetchPosts();
            })
            .catch(error => {
                console.error('Sending like error:', error);
                setError(error.message);
            })
            .finally(() => setLoadingLike(false));
    }

    useEffect(() => {
        fetchPosts();
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

        const token=localStorage.getItem('token');
        fetch('http://localhost:8000/api/createPost', {
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
                setFormData({ title: '', content: '' });
                fetchPosts();
            })
            .catch(error => {
                console.error('Create post error:', error);
                setErrorCreate(error.message);
            })
            .finally(() => setCreating(false));
    };

    return (
        <div className="posts">
            <Nav/>
            <form onSubmit={handleSubmit} style={{maxWidth: '70vw', marginTop: '10vh'}}>
                <h2>Create Post</h2>
                <div>
                    <label>Title:</label>
                    <input
                        type="text"
                        name="title"
                        value={formData.title}
                        onChange={handleInputChange}
                        required
                    />
                </div>
                <div>
                    <label>Content:</label>
                    <input
                        className="content"
                        type="text"
                        name="content"
                        value={formData.content}
                        onChange={handleInputChange}
                        required
                    />
                </div>
                <button type="submit" disabled={creating}>
                    {creating ? "Creating..." : "Create Post"}
                </button>
                {errorCreate && <p style={{color: 'red'}}>Error: {errorCreate}</p>}
            </form>

            <h2>Posts</h2>
            <button className="refresh" onClick={fetchPosts} disabled={loadingPost}>
                {loadingPost ? "Ładowanie..." : "Odśwież"}
            </button>
            <ul>
                {posts.map(post => (
                    <li key={post.id}>
                        <strong>{post.user?.email || "Anonim"}</strong> - {post.content} - {post.createdAt.date}
                        <button onClick={() => polub(post.id)} disabled={loadingLike}>
                            {loadingLike ? "Ładowanie..." : "Polub"}
                        </button>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default Posts;