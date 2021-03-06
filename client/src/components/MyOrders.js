import React, { Component } from 'react';
import axios from "axios";
import { Alert, Button, Table,Container ,Row,Col,Card,CardBody,Collapse ,ListGroupItem} from "reactstrap";
import AddProduct from './AddProduct';
import { Input, Modal, ModalBody, ModalFooter, ModalHeader } from "reactstrap";
import { Redirect } from 'react-router-dom'
import {UserContext} from './UserContext';
import MyOrdersList from './MyOrdersList';
import moment from 'moment';
import _ from 'lodash';
class OrdersView extends Component {

state = {
    collapse:false,
    dateFrom:'',
    dateTo:'',
   
}    
    toggle=()=> {
        this.setState({
            collapse: !this.state.collapse,
        }); 
    }
  

    toggle=(order)=> {
        this.setState({ collapse: this.state.collapse === order ? null : order });
    }


    componentDidMount() {

    }


    filterAndSortOrders=(orders)=> {

        let sortedOrders = _.orderBy(orders, (order) => {
            return moment(order.dateStamp)
          }, ['desc']);
        if(this.state.dateFrom !=='' && this.state.dateTo !== '') {
            console.log('two');
            return sortedOrders.filter(order=>
                moment(order.dateStamp).isBetween(this.state.dateFrom, this.state.dateTo));
        }else if (this.state.dateFrom !== '') {
            console.log('from');

            return sortedOrders.filter(order=>
                moment(order.dateStamp).isBetween(this.state.dateFrom, moment()))

        }else if (this.state.dateTo !== ''){
            console.log('to');

            return sortedOrders.filter(order=>
                moment(order.dateStamp).isBetween(moment('2010-01-01').format("YYYY-MM-DD"),this.state.dateTo))
        }
        else {
            console.log('none');

            console.log(sortedOrders);
            return sortedOrders;
        }
    } 
    render() {
        return (
            <UserContext.Consumer>


            { 
                 ({ myOrders  }) => (
                    <Container >
                    <Row className="mt-5 p-1">

                    From<Input className="col-3 ml-3 " onChange={(event)=>{
                        this.setState({
                            dateFrom:event.target.value
                        })
                        console.log(event.target.value);
                      }} type="date" /> To  <Input placeholder="To" className="col-3 ml-3" type="date" onChange={(event)=>{
                          this.setState({
                              dateTo:event.target.value
                            }) 

                      }} />
                    </Row>
                    <Table className="table-striped mt-5">
            <thead className="bg-secondary rounded">
                <tr>
                    <th> Order Date </th>
                    <td>Room </td>
                    <td> Amount </td>
                    <td>Order Status </td>
                    <td> ## </td>
                </tr> 
            </thead>
            <tbody>

                {   
                    Object.keys(this.filterAndSortOrders(myOrders)).map((key, index) =>
                    <MyOrdersList
                     key={index} 
                     order={myOrders[key]}
                     isOpen={this.state.collapse === myOrders[key]}
                    toggle={this.toggle}
                     />
                )
                }
                

             </tbody>
             </Table>
             </Container>

            )}
            </UserContext.Consumer>
        );
    }
}

export default OrdersView ;