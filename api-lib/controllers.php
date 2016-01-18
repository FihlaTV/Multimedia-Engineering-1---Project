<?php

function getResourceController ( $id ) {
	$sql = 'SELECT * FROM userdata WHERE id = :id';
	$pdo = getConnection();

	try {
		$stmt = $pdo->prepare( $sql );
		$stmt->bindValue( ':id', $id );
		$stmt->execute();
		$entry = $stmt->fetch( PDO::FETCH_ASSOC );

		if ( $entry === false || !is_array( $entry ) )
			sendErrorResponse( 'entryNotFound', 404 );

		$entry = filterResponseData( $entry );
		sendSuccessResponse( $entry );
	} catch ( PDOException $e ) {
		sendErrorResponse( 'serverError', 500 );
	}
}

function getCollectionController () {
	$sql = 'SELECT * FROM userdata ORDER BY id DESC';
	$pdo = getConnection();
	try {
		$stmt = $pdo->prepare( $sql );
		$stmt->execute();

		$results = [];
		while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
			$results[] = filterResponseData( $row );
		}

		sendSuccessResponse( $results );
	} catch ( PDOException $e ) {
		sendErrorResponse( 'serverError', 500 );
	}
}

function getController ( $id ) {
	if ( $id < 1 ) {
		getCollectionController();
	} else {
		getResourceController( $id );
	}
}

function postController ( $data ) {
	$data = validateData( $data );
	//geokoordinaten berechnen -> longitude, latitude in geocoding.php auslagern
	//$data = geocode( $data );
	$pdo = getConnection();
	try {
		$stmt = pdoGenerateWritingStatement( $pdo, 'INSERT INTO userdata SET', ';', $data );
		$stmt->execute();
		$id = (int) $pdo->lastInsertId();
		getResourceController( $id );
	} catch ( PDOException $e ) {
		error_log( $e->getMessage() );
		sendErrorResponse( 'serverError', 500 );
	}
}

function putController ( $id, $data ) {
	if ( $id < 0 )
		sendErrorResponse( "noIdSpecified", 400 );

	$data = validateData( $data );
	$pdo = getConnection();
	try {
		$stmt = pdoGenerateWritingStatement( $pdo, 'UPDATE userdata SET', 'WHERE id = :id;', $data );
		$stmt->bindValue( ':id', $id, PDO::PARAM_INT );
		$stmt->execute();
		getResourceController( $id );
	} catch ( PDOException $e ) {
		error_log( $e->getMessage() );
		sendErrorResponse( 'serverError', 500 );
	}
}

function deleteController ( $id ) {
	if ( $id < 0 )
		sendErrorResponse( "noIdSpecified", 400 );

	$pdo = getConnection();
	$sql = "DELETE FROM userdata WHERE id = :id;";
	try {
		$stmt = $pdo->prepare( $sql );
		$stmt->bindValue( ':id', $id, PDO::PARAM_INT );
		$stmt->execute();
		sendSuccessResponse( null );
	} catch ( PDOException $e ) {
		error_log( $e->getMessage() );
		sendErrorResponse( 'serverError', 500 );
	}
}