import './app.css';
import { useEffect, useRef, useState } from 'react';

const api = 'http://localhost/qmarkets2';

function App() {
  const [input, setInput] = useState('');
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);
  const notFoundInputs = useRef([]);

  const handleChange = (e) => {
    setInput(e.target.value);
  };

  useEffect(() => {
    const shouldCallApi =
      input.length > 2 && !notFoundInputs.current.includes(input);

    if (shouldCallApi) {
      setLoading(true);
    }

    const timeoutHandler = setTimeout(() => {
      if (shouldCallApi) {
        fetch(`${api}/?s=${input}`)
          .then((res) => res.json())
          .then(
            (result) => {
              setUsers(result);
              if (result.length === 0) {
                notFoundInputs.current.push(input);
              }
            },
            (error) => {}
          )
          .finally(() => {
            setLoading(false);
          });
      } else {
        setUsers([]);
      }
    }, 500);

    return () => {
      clearTimeout(timeoutHandler);
      setLoading(false);
    };
  }, [input]);

  return (
    <div className="App">
      <h1>User Search</h1>
      <input
        type="text"
        placeholder="Search User"
        onChange={handleChange}
        value={input}
      />
      {loading ? (
        <p>Loading...</p>
      ) : (
        users.map((user) => (
          <p key={user.id}>
            <b>Full Name:</b> {user['full name']}
            <br />
            <b>Email:</b> {user['mail']}
            <br />
            <b>Username:</b> {user['name']}
          </p>
        ))
      )}

      {!users.length && !loading && <p>No users found</p>}
    </div>
  );
}

export default App;
