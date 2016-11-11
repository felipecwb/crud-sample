
class Container extends React.Component {
    render() {
        return (
            <div className="ui raised very padded container segment">
                <h2 className="ui header">{this.props.header}</h2>
                <div>
                    {this.props.children}
                </div>
            </div>
        );
    }
}

class Button extends React.Component {
    click(e) {
        e.preventDefault();

        let $form = jQuery('form.login');

        $form.find('.message').addClass('hidden');

        let email = $form.find('input[name=email]').val(),
            password = $form.find('input[name=password]').val();

        jQuery.ajax({
            url: '/account/login',
            method: 'POST',
            dataType: 'json',
            data: {'email': email, 'password': password},
            beforeSend: () => $form.addClass('loading'),
            complete: (xhr) => {
                $form.removeClass('loading');

                let result = xhr.responseJSON;

                if (result.status == 0) {
                    window.location.href = '/backend';
                    return;
                }

                let message;
                switch (result.status) {
                    case 1: message = 'Email Inválido'; break;
                    case 2: message = 'Usuário não encontrado'; break;
                    case 3: message = 'Senha Incorreta'; break;
                }

                window.alert(message);
            }
        });
    }
    render () {
        return (
            <button className="ui primary button" onClick={this.click}>Login</button>
        );
    }
}

class Field extends React.Component {
    render() {
        return (
            <div className="field">
                <label>{this.props.label}</label>
                <input type={this.props.type} name={this.props.name} placeholder={this.props.label} />
            </div>
        );
    }
}

class Form extends React.Component {
    render() {
        return (
            <form className="ui form login">
                <Field label="Email" name="email" type="email"/>
                <Field label="Senha" name="password" type="password"/>
                <Button />
                <div className="ui error message"><p></p></div>
            </form>
        );
    }
}

ReactDOM.render(
    <Container header="Login!">
        <Form/>
    </Container>,
    document.querySelector('#root')
);
