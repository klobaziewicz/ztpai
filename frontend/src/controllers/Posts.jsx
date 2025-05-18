import React, { useEffect, useState } from "react";
import Nav from "./Nav";
import {Link} from "react-router-dom";

function Posts() {
    const [posts, setPosts] = useState([]);
    const [loadingPost, setLoadingPost] = useState(false);
    const [loadingLike, setLoadingLike] = useState(false);
    const [error, setError] = useState(null);

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

    return (
        <div>
            <Nav/>
            <h2>Posts</h2>
            <button onClick={fetchPosts} disabled={loadingPost}>
                {loadingPost ? "Ładowanie..." : "Odśwież"}
            </button>
            <ul>
                {posts.map(post => (
                    <li key={post.id}>
                            <strong>{post.user?.username || "Anonim"}</strong> - {post.content} - {post.createdAt.date}
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