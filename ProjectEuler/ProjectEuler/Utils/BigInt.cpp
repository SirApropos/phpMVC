#include "BigInt.h"

BigInt::BigInt(void)
{
	init();
}


BigInt::~BigInt(void)
{
}

void BigInt::init(__int64 value){
	if(value < 0){
		value = -value;
		negative = true;
	}else{
		negative = false;
	}
	buckets = new List<__int64>();
	buckets->add(value);
	normalize();
}

BigInt::BigInt(float f){
	init((__int64)f);
}
BigInt::BigInt(long l){
	init((__int64)l);
}
BigInt::BigInt(int i){
	init((__int64)i);
}
BigInt::BigInt(__int64 i){
	init(i);
}
BigInt::BigInt(double d){
	init((__int64)d);
}

BigInt::BigInt(BigInt& other){
	buckets = new List<__int64>();
	//For some reason, list copy-constructor isn't working right.
	//Figure that out later.
	other.buckets->foreach([&](__int64 value){
		buckets->add(value);
		return false;
	});
	negative = other.negative;
//	buckets = new List<__int64>(*(other.buckets));
}

void BigInt::add(BigInt& param){
	int i=0;
	param.buckets->foreach([&](__int64 value){
		/*if(negative != param.negative){
			value = -value;
		}*/
		addToBucket(i++,value);
		return false;
	});
	normalize();

}
//Will this actually work? Who knows!
void BigInt::subtract(BigInt& param){
	negative = !negative;
	add(param);
	negative = !negative;
}

void BigInt::multiply(BigInt& bi){
	BigInt value = BigInt(*this);
	for(BigInt i = 1;i < bi; i++){
		add(value);
	}
}
void BigInt::divide(BigInt& bi){}

void BigInt::addToBucket(int bucket, __int64 value){
	if(bucket >= buckets->size()){
		for(int i=buckets->size();i<=bucket;i++){
			buckets->add(0);
		}
	}
	buckets->set(bucket,buckets->get(bucket)+value);
}

void BigInt::normalize(int bucket){
	int i=0;
	buckets->foreach([&](__int64 value){
		int carry = 0;
		while(value > MAX_SIZE){
			carry++;
			value-=MAX_SIZE;
		}
		if(i < 0){
			if(i == buckets->size()-1){
				value = -value;
				negative = !negative;
			}else{
				while(value < 0){
					carry--;
					value+=MAX_SIZE;
				}
			}
		}
		if(carry != 0){
			buckets->set(i,value);
			addToBucket(i+1,carry);
		}
		i++;
		return false;
	});
	if(buckets->last() < 0){
	}
}

List<int> BigInt::toList(){
	List<int> digits = List<int>();
	buckets->foreach([&](__int64 bucket){
		while(bucket > 9){
			int digit = bucket % 10;
			digits.add(digit);
			bucket /= 10;
		}
		digits.add((int)bucket);
		return false;
	});
	return digits;
}

int * BigInt::toArray(){
	List<int> digits = toList();
	int * result = new int[digits.size()];
	int i=1;
	digits.foreach([&](int digit){
		i++;
		result[digits.size() - i] = digit;
		return false;
	});
	return result;
}

BigInt& BigInt::pow(int exponent){
	BigInt * result;
	if(exponent > 0){
		result = new BigInt(*this);
		BigInt value = BigInt(*this);
		for(int i=1;i<exponent;i++){
			result->multiply(value);
		}
	}else{
		result = new BigInt();
	}
	return *result;
}

BigInt& BigInt::operator++(){
	addToBucket(0,1);
	normalize();
	return *this;
}
BigInt& BigInt::operator++(int){
	BigInt * result = new BigInt(*this);
	++*this;
	return *result;
}

BigInt& BigInt::operator--(){
	addToBucket(0,-1);
	normalize();
	return *this;
}
BigInt& BigInt::operator--(int){
	BigInt * result = new BigInt(*this);
	--*this;
	return * result;
}

BigInt& BigInt::operator+ (BigInt& param){
	BigInt * result = new BigInt(*this);
	result->add(param);
	return *result;
}
BigInt& BigInt::operator- (BigInt& param){
	BigInt * result = new BigInt(*this);
	result->subtract(param);
	return *result;
}
BigInt& BigInt::operator* (BigInt& param){ 
	BigInt * result = new BigInt(*this);
	result->multiply(param);
	return *result;
}
BigInt& BigInt::operator/ (BigInt& param){
	BigInt * result = new BigInt(*this);
	result->divide(param);
	return *result;
}
BigInt& BigInt::operator+= (BigInt& param){
	add(param);
	return *this;
}
BigInt& BigInt::operator-= (BigInt& param){
	subtract(param);
	return *this;
}
BigInt& BigInt::operator*= (BigInt& param){
	multiply(param);
	return *this;
}
BigInt& BigInt::operator/= (BigInt& param){
	divide(param);
	return *this;
}
BigInt& BigInt::operator+ (__int64 param){
	return *new BigInt(param) + *this;
}
BigInt& BigInt::operator- (__int64 param){ 
	return *new BigInt(param) - *this;
}
BigInt& BigInt::operator* (__int64 param){
	return *new BigInt(param) * *this;
}
BigInt& BigInt::operator/ (__int64 param){ 
	return *new BigInt(param) / *this;
}
BigInt& BigInt::operator+= (__int64 param){ 
	return *this += BigInt(param);
}
BigInt& BigInt::operator-= (__int64 param){ 
	return *this -= BigInt(param);
}
BigInt& BigInt::operator*= (__int64 param){
	return *this *= BigInt(param);
}
BigInt& BigInt::operator/= (__int64 param){
	return *this /= BigInt(param);
}
bool BigInt::operator== (BigInt& param){ 
	bool result = true;
	if(param.buckets->size() == buckets->size()){
		for(int i=0;i<buckets->size();i++){
			if(buckets->get(i) != param.buckets->get(i)){
				result = false;
				break;
			}
		}
	}else{
		result = false;
	}
	return result;
}
bool BigInt::operator!= (BigInt& param){ return !(*this == param); }
bool BigInt::operator== (__int64 param){
	bool result = false;
	if(param > MAX_SIZE){
		result = BigInt(param) == *this;
	}else{
		result = (buckets->size() == 1 && buckets->get(0) == param);
	}
	return result;
}
bool BigInt::operator!= (__int64 param){ return !(*this == param); }
bool BigInt::operator< (BigInt& param){
	bool result = true;
	if(buckets->size() > param.buckets->size()){
		result = false;
	}else if(buckets->size() == param.buckets->size()){
		for(int i=buckets->size()-1;i>=0;i--){
			if(buckets->get(i) > param.buckets->get(i)){
				result = false;
				break;
			}
		}
		if(result){
			result = !(*this == param); //Make sure they're not equal.
		}
	}

	return result;
}
bool BigInt::operator> (BigInt& param){ return !(*this < param);}
bool BigInt::operator< (__int64 param){ return *this < BigInt(param);}
bool BigInt::operator> (__int64 param){ return !(*this < param);}

std::ostream& operator<< (std::ostream& os, BigInt& other){
	os << other.toString();
	return os;
}
std::string BigInt::toString(){
	std::stringstream ss;
	for(int i=buckets->size()-1;i>=0;i--){
		ss << buckets->get(i);
	}
	return ss.str();
}