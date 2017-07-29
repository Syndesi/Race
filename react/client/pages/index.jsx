import React from 'react';
import {observer} from 'mobx-react';
import Page from '../components/page.jsx';


@observer
export default class Index extends React.Component {

  render(){
    return (
      <Page>
        <p>Index Site</p>
      </Page>
    );
  }
}