import React, { useEffect, useState } from "react";
import Nav from "./Nav";
import {Link} from "react-router-dom";

function Posts() {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const fetchPosts = () => {
        setLoading(true);
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
            .finally(() => setLoading(false));
    };

    useEffect(() => {
        fetchPosts();
    }, []);

    return (
        <div>
            <Nav/>
            <h2>Posts</h2>
            <button onClick={fetchPosts} disabled={loading}>
                {loading ? "Loading..." : "Refresh"}
            </button>
            <ul>
                {posts.map(post => (
                    <li key={post.id}>
                        <strong>{post.user?.username || "Anonim"}</strong> - {post.content} - {post.createdAt.date}
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default Posts;