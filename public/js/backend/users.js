
class Header extends React.Component {
    logout() {
        let $root = jQuery('#root');

        jQuery.ajax({
            url: '/account/logout',
            method: 'POST',
            dataType: 'json',
            data: {'kickoff': true},
            beforeSend: () => $root.addClass('loading'),
            complete: (xhr) => {
                $root.removeClass('loading');

                let result = xhr.responseJSON;

                if (result.status == 0) {
                    window.location.href = '/account';
                    return;
                }

                window.alert(result.message);
            }
        });
    }
    render() {
        return (
            <div className="ui header grid">
                <div className="ten wide column left">
                    <h3>Bem vindo {USER.name}!</h3>
                </div>
                <div className="four wide column right">
                    <button className="ui red button logout" onClick={this.logout}>Logout</button>
                </div>
            </div>
        );
    }
}

class Container extends React.Component {
    render() {
        return (
            <div className="ui raised very padded container segment">
                <Header />
                <hr />
                <br /><br />
                <div className="ui grid">
                    {this.props.children}
                </div>
            </div>
        );
    }
}

class UsersTable extends React.Component {
    constructor(props) {
        super(props);

        jQuery.ajax({
            url: '/api/user',
            method: 'GET',
            async: false,
            dataType: 'json',
            complete: (xhr) => {
                this.state = {users: xhr.responseJSON};
            }
        });
    }

    render() {
        let rows = this.state.users.map(user => <UserRow user={user}/>);

        return (
            <table className="ui striped table users">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>E-mail</th>
                  <th>Birth Date</th>
                  <th>Perfil</th>
                  <th>Alterar</th>
                  <th>Remover</th>
                </tr>
              </thead>
              <tbody>
                {rows}
              </tbody>
            </table>
        );
    }
}

class UserRow extends React.Component {
    constructor(props) {
        super(props);
        this.props.user.birthDate = new Date(this.props.user.birthDate + 'T00:00:00-03:00');

    }
    render() {
        return (
            <tr className="user" data-id={this.props.user.id}>
              <td>{this.props.user.name}</td>
              <td>{this.props.user.email}</td>
              <td>{this.props.user.birthDate.toLocaleDateString('pt-BR')}</td>
              <td>{this.props.user.profile.name}</td>
              <td><ButtonUserUpdate user={this.props.user} /></td>
              <td><ButtonUserDelete user={this.props.user} /></td>
            </tr>
        );
    }
}

class ButtonUserUpdate extends React.Component {
    click() {
        alert('update '  + this.props.user.name);
    }
    render() {
        return (
            <button onClick={this.click.bind(this)}><i className="refresh icon"></i></button>
        );
    }
}

class ButtonUserDelete extends React.Component {
    click() {
        if (! confirm(`Deletar ${this.props.user.name}?`)) {
            return;
        }

        let $r = jQuery('#root');

        jQuery.ajax({
            url: '/api/user/' + this.props.user.id,
            method: 'DELETE',
            dataType: 'json',
            beforeSend: () => $r.addClass('loading');
            complete: (xhr) => {
                $r.removeClass('loading');
                result = xhr.responseJSON;

                if (result.status == 0) {
                    jQuery(`table.users tr.user[data-id=${this.props.user.id}]`)
                        .hide(400, () => jQuery(this).remove());

                    alert('Removido com sucesso!');
                    return;
                }

                alert(result.message);
            }
        });
    }
    render() {
        return (
            <button onClick={this.click.bind(this)}><i className="erase icon"></i></button>
        );
    }
}

ReactDOM.render(
    <Container>
        <UsersTable />
    </Container>,
    document.querySelector('#root')
)
