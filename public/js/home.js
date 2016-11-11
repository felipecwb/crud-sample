
class Home extends React.Component {
    render() {
        return (
            <div className="ui raised very padded text container segment">
                <h2 className="ui header">Seja bem vindo!</h2>
                <p>
                    <a className="ui primary button" href="/account">Fazer Login</a>
                </p>
            </div>
        );
    }
}

ReactDOM.render(
    <Home />,
    document.querySelector('#root')
);
